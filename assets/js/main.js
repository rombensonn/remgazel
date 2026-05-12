(() => {
    const qs = new URLSearchParams(window.location.search);
    const utmFields = ['utm_source', 'utm_medium', 'utm_campaign'];
    const nav = document.querySelector('[data-nav]');
    const toggle = document.querySelector('[data-menu-toggle]');

    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(isOpen));
        });

        nav.addEventListener('click', (event) => {
            if (event.target instanceof HTMLAnchorElement) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const id = link.getAttribute('href');
            if (!id || id === '#') return;
            const target = document.querySelector(id);
            if (!target) return;
            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.pushState(null, '', id);
        });
    });

    document.querySelectorAll('input[name="page_url"]').forEach((input) => {
        input.value = window.location.href;
    });

    utmFields.forEach((field) => {
        document.querySelectorAll(`input[name="${field}"]`).forEach((input) => {
            input.value = qs.get(field) || '';
        });
    });

    const revealItems = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        revealItems.forEach((item) => observer.observe(item));
    } else {
        revealItems.forEach((item) => item.classList.add('is-visible'));
    }

    const isPhoneValid = (value) => {
        const digits = value.replace(/\D/g, '');
        return digits.length >= 10 && digits.length <= 15;
    };

    const setMessage = (form, text, type) => {
        const message = form.querySelector('.form-message');
        if (!message) return;
        message.textContent = text;
        message.classList.remove('success', 'error');
        if (type) message.classList.add(type);
    };

    const collectPayload = (form) => {
        const data = new FormData(form);
        const payload = {};
        data.forEach((value, key) => {
            payload[key] = String(value);
        });
        return payload;
    };

    document.querySelectorAll('[data-lead-form]').forEach((form) => {
        let locked = false;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (locked) return;

            const phone = form.querySelector('input[name="phone"]');
            const requiredCheckboxes = Array.from(form.querySelectorAll('input[type="checkbox"][required]'));
            const submit = form.querySelector('button[type="submit"]');

            if (!phone || !isPhoneValid(phone.value)) {
                setMessage(form, 'Укажите корректный телефон.', 'error');
                phone?.focus();
                return;
            }

            const uncheckedConsent = requiredCheckboxes.find((checkbox) => !checkbox.checked);
            if (uncheckedConsent) {
                setMessage(form, 'Отметьте оба согласия, чтобы отправить заявку.', 'error');
                uncheckedConsent.focus();
                return;
            }

            locked = true;
            if (submit) {
                submit.disabled = true;
                submit.dataset.originalText = submit.textContent || '';
                submit.textContent = 'Отправляем...';
            }
            setMessage(form, '', '');

            try {
                if (window.location.hostname.endsWith('github.io')) {
                    const payload = collectPayload(form);
                    const text = [
                        'Здравствуйте. Хочу уточнить ремонт.',
                        payload.phone ? `Телефон: ${payload.phone}` : '',
                        payload.car ? `Марка авто: ${payload.car}` : '',
                        payload.issue ? `Проблема: ${payload.issue}` : '',
                    ].filter(Boolean).join('\n');
                    window.open(`https://wa.me/79032960693?text=${encodeURIComponent(text)}`, '_blank', 'noopener');
                    setMessage(form, 'Открыли WhatsApp с текстом заявки. На PHP-хостинге форма отправляется прямо мастеру.', 'success');
                    return;
                }

                const response = await fetch('api/lead.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(collectPayload(form)),
                });
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Не удалось отправить заявку.');
                }

                form.reset();
                form.querySelectorAll('input[name="page_url"]').forEach((input) => {
                    input.value = window.location.href;
                });
                utmFields.forEach((field) => {
                    form.querySelectorAll(`input[name="${field}"]`).forEach((input) => {
                        input.value = qs.get(field) || '';
                    });
                });
                setMessage(form, result.message || 'Заявка отправлена. Мастер свяжется с вами в ближайшее время.', 'success');
            } catch (error) {
                setMessage(form, error.message || 'Не удалось отправить заявку. Попробуйте позвонить в сервис.', 'error');
            } finally {
                window.setTimeout(() => {
                    locked = false;
                    if (submit) {
                        submit.disabled = false;
                        submit.textContent = submit.dataset.originalText || 'Отправить';
                    }
                }, 3500);
            }
        });
    });
})();
