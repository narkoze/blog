<template>
  <th class="is-nowrap">
    <a @click="disabled || $emit('changed', column)">
      <slot></slot>
      <span v-if="sort === column">
        <spinner
          v-if="disabled"
          class="is-small"
        >
        </spinner>
        <i
          v-else
          :class="['fas', {
            'fa-angle-up': direction === 'asc',
            'fa-angle-down': direction === 'desc'
          }]"
        >
        </i>
      </span>
    </a>

    <i
      v-if="filtered"
      class="fas fa-filter fa-xs"
    >
    </i>
  </th>
</template>

<script>
  import Spinner from './spinner.vue'

  export default {
    components: {
      Spinner
    },
    props: [
      'column',
      'sort',
      'direction',
      'disabled',
      'filtered',
    ]
  }
</script>
