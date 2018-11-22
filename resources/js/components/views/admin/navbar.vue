<template>
  <transition
    name="menu-admin"
    appear
  >
    <div class="menu-admin is-nowrap">
      <p class="menu-admin-label">
        {{ $t('posts') }}
      </p>
      <ul>
        <router-link
          :to="{ name: 'admin-posts' }"
          :class="{ 'is-active': $route.name === 'admin-posts' }"
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
      </ul>

      <p class="menu-admin-label">
        {{ $t('users') }}
      </p>
      <ul>
        <router-link
          :to="{ name: 'admin-roles' }"
          :class="{ 'is-active': $route.name === 'admin-roles' }"
          tag="li"
        >
          {{ $t('allroles') }}
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
      "allroles": "Roles"
    },
    "lv": {
      "posts": "Ziņas",
      "allposts": "Visas ziņas",
      "newpost": "Jauna ziņa",
      "users": "Lietotāji",
      "allroles": "Lomas"
    }
  }
</i18n>
