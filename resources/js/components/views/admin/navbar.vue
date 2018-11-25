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

        <router-link
          :to="{ name: 'admin-tags' }"
          :class="{ 'is-active': $route.name === 'admin-tags' }"
          tag="li"
        >
          {{ $t('alltags') }}
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
      "allusers": "Users",
      "allroles": "Roles",
      "alltags": "Tags"
    },
    "lv": {
      "posts": "Ziņas",
      "allposts": "Visas ziņas",
      "newpost": "Jauna ziņa",
      "users": "Lietotāji",
      "allusers": "Lietotāji",
      "allroles": "Lomas",
      "alltags": "Tēmturi"
    }
  }
</i18n>
