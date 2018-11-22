<template>
  <div>
    <div class="field">
      <label :class="['label', { 'has-text-danger': $parent.errors.title_lv }]">
        <p class="control has-icons-left">
          <input
            v-model="$parent.post.title_lv"
            type="text"
            :class="['input', { 'is-danger': $parent.errors.title_lv }]"
            :disabled="$parent.disabled"
            placeholder="Ievadiet virsrakstu šeit"
          >
          <span class="icon is-left">
            <span class="flag-icon flag-icon-lv"></span>
          </span>
        </p>
      </label>
      <p v-if="$parent.errors.title_lv" class="help is-danger">{{ $parent.errors.title_lv.join() }}</p>
    </div>

    <div class="field">
      <spinner v-if="!$parent.content_lv_initialized"></spinner>
      <label :class="['label', { 'children-error': $parent.errors.content_lv }]">
        <textarea
          v-model="$parent.post.content_lv"
          id="content_lv"
          class="is-invisible"
        >
        </textarea>
      </label>
      <p v-if="$parent.errors.content_lv" class="help is-danger">{{ $parent.errors.content_lv.join() }}</p>
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
