<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-half is-offset-one-quarter">
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
</template>

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
