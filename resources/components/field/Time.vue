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
                :max="max"
                format="24hr"
                @update:model-value="menu = false"
            />
        </v-menu>
    </v-text-field>
</template>
<script setup lang="ts">
defineOptions({ name: 'FieldTime' })

defineProps<{
    label: string
    min?: string
    max?: string
}>()

const menu = ref(false)
const model = defineModel<string | null>({ default: null })
</script>
