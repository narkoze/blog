<template>
  <div class="field">
    <label
      class="label"
      :for="label"
    >
      {{ label }}
    </label>
    <datepicker
      :id="label"
      :language="$i18n.locale === 'lv' ? lv : en"
      :full-month-name="true"
      :monday-first="true"
      :typeable="true"
      :value="value"
      input-class="input"
      format="yyyy-MM-dd"
      @selected="selected"
    >
    </datepicker>
  </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker'
  import {
    en,
    lv
  } from 'vuejs-datepicker/dist/locale'
  import moment from 'moment'

  export default {
    components: {
      Datepicker
    },
    props: [
      'label',
      'value',
    ],
    data: () => ({
      en: en,
      lv: lv
    }),
    methods: {
      selected (date) {
        this.$emit('selected', date && moment(date).format('Y-MM-DD'))
      }
    }
  }
</script>

<style lang="scss">
  .vdp-datepicker__calendar .cell.selected {
    color:  hsl(0, 0%, 100%) !important;
    background: hsl(204, 86%, 53%) !important;
  }
  .vdp-datepicker__calendar .cell:not(.blank):not(.disabled).day:hover,
  .vdp-datepicker__calendar .cell:not(.blank):not(.disabled).month:hover,
  .vdp-datepicker__calendar .cell:not(.blank):not(.disabled).year:hover {
    &:not(.selected) {
      border: 1px solid #b5b5b5 !important;
    }
    border: 1px solid transparent !important;
  }
</style>

