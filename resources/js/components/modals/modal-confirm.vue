<template>
  <div class="modal is-active">
    <div
      @click="$emit('close')"
      class="modal-background"
    >
    </div>

    <div class="modal-content">
      <div class="box has-text-centered">
        <h1 class="title is-spaced">{{ $t('title') }}</h1>

        <p class="subtitle is-5">
          <slot></slot>
        </p>

        <div class="buttons is-block">
          <a
            @click="$emit('confirmed')"
            class="button is-primary is-large"
            style="min-width: 100px"
          >
            {{ $t('yes') }}
          </a>

          <a
            @click="$emit('close')"
            class="button is-danger is-large"
            style="min-width: 100px"
          >
            {{ $t('no') }}
          </a>
        </div>
      </div>
    </div>

    <button
      @click="$emit('close')"
      class="modal-close is-large"
    >
    </button>
  </div>
</template>

<i18n>
  {
    "en": {
      "title": "Please confirm",
      "yes": "Yes",
      "no": "No"
    },
    "lv": {
      "title": "Lūdzu apstipriniet",
      "yes": "Jā",
      "no": "Nē"
    }
  }
</i18n>
