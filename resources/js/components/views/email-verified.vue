<template>
  <transition
    name="fade"
    appear
  >
    <section class="section">
      <div class="card is-medium">
        <div class="card-content">
          <div class="content">
            <h1 class="title">{{ $t('title') }}</h1>

            <article class="message is-success">
              <div class="message-body">
                {{ $t('message') }}
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>
  </transition>
</template>

<style>
  .fade-enter-active {
    transition: opacity .5s;
  }
  .fade-leave-active {
    display: none;
  }
  .fade-enter {
    opacity: 0;
  }
</style>

<i18n>
  {
    "en": {
      "title": "E-mail verification",
      "message": "Thank you, your e-mail address has been successfully verified!"
    },
    "lv": {
      "title": "E-pasta apliecināšana",
      "message": "Paldies, jūsu e-pasta adrese tika veiksmīgi apliecināta!"
    }
  }
</i18n>
