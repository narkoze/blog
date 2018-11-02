import PasswordReset from './components/views/public/passwordreset.vue'
import EmailVerified from './components/views/public/emailverified.vue'

export default [
  { name: 'home', path: '' },
  { path: 'verified', component: EmailVerified },
  { path: 'passwordreset/:token/:email', component: PasswordReset }
]
