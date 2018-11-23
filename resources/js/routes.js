
import Posts from './components/views/post/posts.vue'
import Post from './components/views/post/post.vue'
import Profile from './components/views/profile.vue'
import Admin from './components/views/admin/admin.vue'
import AdminPost from './components/views/admin/post/post.vue'
import AdminPosts from './components/views/admin/post/posts.vue'
import AdminUsers from './components/views/admin/users.vue'
import AdminRole from './components/views/admin/role/role.vue'
import AdminRoles from './components/views/admin/role/roles.vue'
import PasswordReset from './components/views/password-reset.vue'
import EmailVerified from './components/views/email-verified.vue'

export default [
  {
    name: 'home',
    path: ''
  },
  {
    name: 'posts',
    path: 'posts',
    component: Posts
  },
  {
    name: 'post',
    path: 'post/:id',
    component: Post
  },
  {
    name: 'profile',
    path: 'profile',
    component: Profile
  },
  {
    name: 'admin',
    path: 'admin',
    component: Admin,
    redirect: {
      name: 'admin-posts'
    },
    children: [
      {
        name: 'admin-post',
        path: 'post',
        component: AdminPost
      },
      {
        name: 'admin-post-edit',
        path: 'post/:id',
        component: AdminPost,
        props: true
      },
      {
        name: 'admin-posts',
        path: 'posts',
        component: AdminPosts
      },
      {
        name: 'admin-users',
        path: 'users',
        component: AdminUsers
      },
      {
        name: 'admin-role',
        path: 'role',
        component: AdminRole
      },
      {
        name: 'admin-role-edit',
        path: 'role/:id',
        component: AdminRole,
        props: true
      },
      {
        name: 'admin-roles',
        path: 'roles',
        component: AdminRoles
      }
    ]
  },
  {
    name: 'verified',
    path: 'verified',
    component: EmailVerified
  },
  {
    name: 'passwordreset',
    path: 'passwordreset/:token/:email',
    component: PasswordReset
  },
]
