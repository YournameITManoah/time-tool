<template>
    <v-text-field
        v-model="model"
        :active="menu"
        :focus="menu"
        :label="label"
        prepend-icon="mdi-clock-time-four-outline"
        readonly
    >
        <v-menu
            v-model="menu"
            :close-on-content-click="false"
            activator="parent"
            transition="scale-transition"
        >
            <v-time-picker
                v-if="menu"
                v-model="model"
                :min="min"
                color="primary"
                :max="max"
                format="24hr"
                scrollable
                :use-seconds="!!model && model.split(':').length === 3"
                @update:hour="updateHours"
                @update:minute="updateMinutes"
                @update:model-value="menu = false"
            />
        </v-menu>
    </v-text-field>
</template>
<script setup lang="ts">
defineOptions({ name: 'FieldTime' })

defineProps<{
    label: string
    max?: string
    min?: string
}>()

const menu = ref(false)
const model = defineModel<null | string>({ default: null })

const updateHours = (val: number) => {
    const [, m, s] = model.value?.split(':') ?? ['00', '00']
    model.value = `${pad(val)}:${m}${s ? `:${s}` : ''}`
}

const updateMinutes = (val: number) => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const [h, _m, s] = model.value?.split(':') ?? ['00', '00']
    model.value = `${h}:${pad(val)}${s ? `:${s}` : ''}`
    if (!s) menu.value = false
}
</script>
