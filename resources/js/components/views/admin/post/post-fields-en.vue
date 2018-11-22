<template>
  <div>
    <div class="field">
      <label :class="['label', { 'has-text-danger': $parent.errors.title_en }]">
        <p class="control has-icons-left">
          <input
            v-model="$parent.post.title_en"
            type="text"
            :class="['input', { 'is-danger': $parent.errors.title_en }]"
            :disabled="$parent.disabled"
            placeholder="Enter title here"
          >
          <span class="icon is-left">
            <span class="flag-icon flag-icon-gb"></span>
          </span>
        </p>
      </label>
      <p v-if="$parent.errors.title_en" class="help is-danger">{{ $parent.errors.title_en.join() }}</p>
    </div>

    <div class="field">
      <spinner v-if="!$parent.content_en_initialized"></spinner>
      <label :class="['label', { 'children-error': $parent.errors.content_en }]">
        <textarea
          v-model="$parent.post.content_en"
          id="content_en"
          class="is-invisible"
        >
        </textarea>
      </label>
      <p v-if="$parent.errors.content_en" class="help is-danger">{{ $parent.errors.content_en.join() }}</p>
    </div>
  </div>
</template>

<script>
  import Spinner from '../../../spinner.vue'

  export default {
    name: 'fields-en',
    components: {
      Spinner
    }
  }
</script>
