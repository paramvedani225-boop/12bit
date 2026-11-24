/* Contact form — instant validation + AJAX submit to contact.php.
   Without JS the form still posts normally and contact.php validates server-side. */
(function () {
  const S = window.SITE;
  const form = document.getElementById('inquiry');
  const status = form.querySelector('.form-status');
  const btn = form.querySelector('button[type="submit"]');

  const fields = [...form.querySelectorAll('input[required], select[required], textarea[required]')];
  const check = el => {
    const ok = el.checkValidity() && el.value.trim() !== '';
    el.classList.toggle('is-invalid', !ok);
    el.closest('.field').classList.toggle('has-error', !ok);
    return ok;
  };
  fields.forEach(el => {
    el.addEventListener('blur', () => check(el));
    el.addEventListener('input', () => el.classList.contains('is-invalid') && check(el));
    el.addEventListener('change', () => check(el));
  });
  const show = (cls, msg) => { status.className = 'form-status show ' + cls; status.textContent = msg; };
  const markServerErrors = errors => Object.keys(errors || {}).forEach(name => {
    const el = form.elements[name];
    if (el) { el.classList.add('is-invalid'); el.closest('.field').classList.add('has-error'); }
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const bad = fields.filter(el => !check(el));
    if (bad.length) { bad[0].focus(); show('bad', 'Please correct the highlighted fields.'); return; }

    btn.disabled = true; btn.querySelector('span').textContent = 'Sending…';
    try {
      const res = await fetch(S.form.endpoint || form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } });
      const data = await res.json().catch(() => ({}));
      if (res.ok && data.ok) {
        form.reset();
        show('ok', data.message);
      } else {
        markServerErrors(data.errors);
        show('bad', data.message || ('Sorry, the enquiry could not be sent. Please try again or email ' + S.email + '.'));
      }
    } catch (err) {
      show('bad', 'Sorry, the enquiry could not be sent. Please try again or email ' + S.email + '.');
    } finally {
      btn.disabled = false; btn.querySelector('span').textContent = 'Send enquiry';
    }
  });
})();
