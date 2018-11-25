<template>
  <div class="field">
    <label
      @click="$refs.multiselect.$el.focus()"
      class="label is-for-multiselect"
    >
      {{ $t('label') }}
    </label>
    <multiselect
      ref="multiselect"
      :options="options"
      :value="selected"
      :custom-label="customLabel"
      :show-labels="false"
      :show-no-options="false"
      :searchable="false"
      class="is-multiselect is-pointer"
      placeholder=""
      @input="selected => $emit('selected', selected)"
    >
      <span
        slot="caret"
        class="arrow"
      >
        <i class="fas fa-angle-down fa-lg"></i>
      </span>
    </multiselect>
  </div>
</template>

<script>
  import Multiselect from 'vue-multiselect'

  export default {
    components: {
      Multiselect
    },
    props: [
      'selected',
    ],
    data: () => ({
      options: [
        'published',
        'draft',
      ]
    }),
    methods: {
      customLabel (value) {
        return this.$t(value)
      }
    }
  }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<i18n>
  {
    "en": {
      "label": "State",
      "draft": "Draft",
      "published": "Published"
    },
    "lv": {
      "label": "Status",
      "draft": "Melnraksts",
      "published": "Publicēts"
    }
  }
</i18n>
