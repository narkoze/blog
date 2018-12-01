<template>
  <transition
    name="menu-admin"
    appear
  >
    <div class="menu-admin is-nowrap">
      <ul>
        <router-link
          :to="{ name: 'admin-dashboard' }"
          :class="{ 'is-active': $route.name === 'admin-dashboard' }"
          tag="li"
        >
          {{ $t('dashboard') }}
        </router-link>
      </ul>

      <p class="menu-admin-label">
        {{ $t('users') }}
      </p>
      <ul>
        <router-link
          :to="{ name: 'admin-users' }"
          :class="{ 'is-active': $route.name === 'admin-users' }"
          tag="li"
        >
          {{ $t('allusers') }}
        </router-link>

        <router-link
          :to="{ name: 'admin-roles' }"
          :class="{ 'is-active': ['admin-roles', 'admin-role', 'admin-role-edit'].includes($route.name) }"
          tag="li"
        >
          {{ $t('allroles') }}
        </router-link>
      </ul>

      <p class="menu-admin-label">
        {{ $t('posts') }}
      </p>
      <ul>
        <router-link
          :to="{ name: 'admin-posts' }"
          :class="{ 'is-active': ['admin-posts', 'admin-post-edit'].includes($route.name) }"
          tag="li"
        >
          {{ $t('allposts') }}
        </router-link>

        <router-link
          :to="{
            name: 'admin-post',
            post: {
              id: null
            }
          }"
          :class="{ 'is-active': $route.name === 'admin-post' }"
          tag="li"
        >
          <i class="fas fa-plus"></i>
          {{ $t('newpost') }}
        </router-link>

        <router-link
          :to="{ name: 'admin-tags' }"
          :class="{ 'is-active': ['admin-tags', 'admin-tag', 'admin-tag-edit'].includes($route.name) }"
          tag="li"
        >
          {{ $t('alltags') }}
        </router-link>

        <router-link
          :to="{ name: 'admin-images' }"
          :class="{ 'is-active': ['admin-images', 'admin-image-edit', 'admin-image-upload'].includes($route.name) }"
          tag="li"
        >
          {{ $t('allimages') }}
        </router-link>
      </ul>
    </div>
  </transition>
</template>

<i18n>
  {
    "en": {
      "posts": "Posts",
      "allposts": "All posts",
      "newpost": "New post",
      "users": "Users",
      "allusers": "Users",
      "allroles": "Roles",
      "alltags": "Tags",
      "allimages": "Images",
      "dashboard": "Dashboard"
    },
    "lv": {
      "posts": "Ziņas",
      "allposts": "Visas ziņas",
      "newpost": "Jauna ziņa",
      "users": "Lietotāji",
      "allusers": "Lietotāji",
      "allroles": "Lomas",
      "alltags": "Tēmturi",
      "allimages": "Attēli",
      "dashboard": "Informācija"
    }
  }
</i18n>
