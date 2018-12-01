<template>
  <div
    :id="`progressbar-${id}`"
    class="progressbar"
  >
  </div>
</template>

<script>
  import ProgressBar from 'progressbar.js'

  export default {
    props: [
      'id',
      'value',
      'uploaded'
    ],
    data: () => ({
      circle: null
    }),
    watch: {
      value (value) {
        this.circle.set(value / 100)

        if (value > 0) {
          this.circle.setText(`${value}%`)
        } else {
          this.circle.setText('')
        }
      },
      uploaded (uploaded) {
        if (uploaded) {
          let label = document.getElementsByClassName(`progressbar__label__${this.id}`)[0]
          label.innerHTML = '<i class="fas fa-check-circle fa-6x"></i>'
        }
      }
    },
    mounted () {
      this.circle = new ProgressBar.Circle(`#progressbar-${this.id}`, {
        easing: 'easeInOut',
        strokeWidth: 10,
        svgStyle: {
          display: 'block',
          width: '33%'
        },
        text: {
          style: {
            color: '#00d1b2',
            padding: 0,
            margin: 0,
            position: 'absolute',
            left: '50%',
            top: '50%',
            transform: {
              prefix: true,
              value: 'translate(-50%, -50%)'
            }
          },
          className: `progressbar__label__${this.id}`
        },
        from: { color: '#ff3860' },
        to: { color: '#00d1b2' },
        step: (state, circle) => {
          let label = document.getElementsByClassName(`progressbar__label__${this.id}`)
          if (label.length) label[0].style.color = state.color

          circle.path.setAttribute('stroke', state.color)
        }
      })
    },
    beforeDestroy  () {

    }
  }
</script>

<style>
  .progressbar {
    position: absolute;
    height: 230px;
    display: flex;
    justify-content: center;
    pointer-events: none;
  }
  .progressbar__label {
    font-weight: 700;
  }
</style>
