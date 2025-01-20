import mitt from 'mitt'

const emitter = mitt<{
    'time-log:refresh': true
}>()

export { emitter }
