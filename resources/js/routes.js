import PasswordReset from './components/views/public/password-reset.vue'
import EmailVerified from './components/views/public/email-verified.vue'
import Profile from './components/views/profile.vue'

export default [
  { name: 'home', path: '' },
  { path: 'verified', component: EmailVerified },
  { path: 'passwordreset/:token/:email', component: PasswordReset },
  {
    name: 'profile',
    path: 'profile',
    component: Profile,
    meta: {
      requiresAuth: true
    }
  },
]
