<template>
  <div>
    <div class="field">
      <label :class="['label', { 'has-text-danger': $parent.errors.name_en }]">
        <p class="control has-icons-left">
          <input
            v-model="$parent.role.name_en"
            type="text"
            :class="['input', { 'is-danger': $parent.errors.name_en }]"
            :disabled="$parent.disabled"
            placeholder="Enter name here"
          >
          <span class="icon is-left">
            <span class="flag-icon flag-icon-gb"></span>
          </span>
        </p>
      </label>
      <p v-if="$parent.errors.name_en" class="help is-danger">{{ $parent.errors.name_en.join() }}</p>
    </div>

    <div class="field">
      <label class="label">
        <textarea
          v-model="$parent.role.description_en"
          :class="['textarea', { 'is-danger': $parent.errors.description_en }]"
          placeholder="Description"
        >
        </textarea>
      </label>
      <p v-if="$parent.errors.description_en" class="help is-danger">{{ $parent.errors.description_en.join() }}</p>
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
