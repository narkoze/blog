<template>
  <a
    @click="$root.showModalSignin = true"
    class="button is-info"
  >
    <span class="icon">
      <i class="fas fa-sign-in-alt"></i>
    </span>
    <span>{{ $t('signin') }}</span>
  </a>
</template>

<i18n>
  {
    "en": {
      "signin": "Sign in"
    },
    "lv": {
      "signin": "Ienākt"
    }
  }
</i18n>
