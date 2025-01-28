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
                class="time-picker"
                :use-seconds="useSeconds"
                @update:minute="menu = useSeconds"
                @update:second="menu = false"
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

const useSeconds = computed(() => {
    return !!model.value && model.value.split(':').length === 3
})
</script>
<style lang="scss" scoped>
.time-picker :deep(.v-time-picker-controls__time__btn__active) {
    color: rgb(var(--v-theme-primary));
}
</style>
