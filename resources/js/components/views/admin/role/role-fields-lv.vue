<template>
  <div>
    <div class="field">
      <label :class="['label', { 'has-text-danger': $parent.errors.name_lv }]">
        <p class="control has-icons-left">
          <input
            v-model="$parent.role.name_lv"
            type="text"
            :class="['input', { 'is-danger': $parent.errors.name_lv }]"
            :disabled="$parent.disabled"
            placeholder="Ievadiet nosaukumu šeit"
          >
          <span class="icon is-left">
            <span class="flag-icon flag-icon-lv"></span>
          </span>
        </p>
      </label>
      <p v-if="$parent.errors.name_lv" class="help is-danger">{{ $parent.errors.name_lv.join() }}</p>
    </div>

    <div class="field">
      <label class="label">
        <textarea
          v-model="$parent.role.description_lv"
          :class="['textarea', { 'is-danger': $parent.errors.description_lv }]"
          placeholder="Apraksts"
        >
        </textarea>
      </label>
      <p v-if="$parent.errors.description_lv" class="help is-danger">{{ $parent.errors.description_lv.join() }}</p>
    </div>
  </div>
</template>

<script>
  import Spinner from '../../../spinner.vue'

  export default {
    name: 'fields-lv',
    components: {
      Spinner
    }
  }
</script>
