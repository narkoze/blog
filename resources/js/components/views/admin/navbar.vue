<template>
  <transition
    name="menu-admin"
    appear
  >
    <div class="menu-admin">
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
    </div>
  </transition>
</template>

<i18n>
  {
    "en": {
      "posts": "Posts",
      "allposts": "All posts",
      "newpost": "New post"
    },
    "lv": {
      "posts": "Ziņas",
      "allposts": "Visas ziņas",
      "newpost": "Jauna ziņa"
    }
  }
</i18n>
