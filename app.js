const currencyFlags = ['🇮🇳', '🇺🇸', '🇬🇧', '🇪🇺', '🇦🇪']

document.querySelectorAll('.currency').forEach((control) => {
  const select = control.querySelector('select')
  const flag = control.querySelector('.flag')
  if (!select || !flag) return
  select.addEventListener('change', () => {
    flag.textContent = currencyFlags[Number(select.value)] || currencyFlags[0]
  })
})

const params = new URLSearchParams(window.location.search)
if (params.get('registered') === '1') {
  const form = document.querySelector('#registration-form')
  const message = document.createElement('p')
  message.className = 'form-success'
  message.setAttribute('role', 'status')
  message.textContent = 'You’re on the list! We’ll keep you posted on our launch.'
  form?.querySelector('.submit')?.after(message)
}
if (params.get('error') === '1') {
  const form = document.querySelector('#registration-form')
  const message = document.createElement('p')
  message.className = 'form-error'
  message.setAttribute('role', 'alert')
  message.textContent = 'We could not complete your registration. Please review the form and try again.'
  form?.querySelector('.submit')?.after(message)
}
