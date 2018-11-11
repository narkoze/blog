<template>
  <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>

    <div class="pswp__scroll-wrap">
      <div class="pswp__container">
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
      </div>

      <div class="pswp__ui pswp__ui--hidden">
        <div class="pswp__top-bar">
          <div class="pswp__counter"></div>
          <button class="pswp__button pswp__button--close" :title="$t('close')"></button>
          <button class="pswp__button pswp__button--fs" :title="$t('fullscreen')"></button>
          <button class="pswp__button pswp__button--zoom" :title="$t('zoom')"></button>
          <div class="pswp__preloader">
            <div class="pswp__preloader__icn">
              <div class="pswp__preloader__cut">
                <div class="pswp__preloader__donut"></div>
              </div>
            </div>
          </div>
        </div>

        <button class="pswp__button pswp__button--arrow--left" :title="$t('previous')"></button>

        <button class="pswp__button pswp__button--arrow--right" :title="$t('next')"></button>

        <div class="pswp__caption">
          <div class="pswp__caption__center"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<style src="photoswipe/dist/photoswipe.css"></style>
<style src="photoswipe/dist/default-skin/default-skin.css"></style>
<script>
  import PhotoSwipe from 'photoswipe'
  import PhotoSwipeUIDefault from 'photoswipe/dist/photoswipe-ui-default.js'

  export default {
    props: {
      items: {
        type: Array,
        required: true,
        default: () => []
      },
      index: {
        type: Number,
        default: 0
      }
    },
    data: function () {
      return {
        options: {
          index: this.index,
          history: false,
          closeOnScroll: false,
          errorMsg: `<div class="pswp__error-msg">${this.$t('error')}</div>`
        },
        gallery: null
      }
    },
    mounted () {
      if (this.items.length) {
        this.gallery = new PhotoSwipe(this.$el, PhotoSwipeUIDefault, this.items, this.options)

        this.gallery.listen('close', () => this.$emit('close'))

        this.gallery.init()
      }
    }
  }
</script>

<i18n>
  {
    "en": {
      "close": "Close (Esc)",
      "fullscreen": "Toggle fullscreen",
      "zoom": "Zoom in/out",
      "previous": "Previous (arrow left)",
      "next": "Next (arrow right)",
      "error": "failed to display"
    },
    "lv": {
      "close": "Aizvērt (Esc)",
      "fullscreen": "Pārslēgt pilnekrāna režīmā",
      "zoom": "Pietuvināt / Attālināt",
      "previous": "Iepriekšējais (bulta pa kreisi)",
      "next": "Nākošais (bulta pa labi)",
      "error": "Neizdevās attēlot"
    }
  }
</i18n>
