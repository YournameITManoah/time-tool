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
            <v-time-picker v-if="menu" v-model="model" format="24hr" />
        </v-menu>
    </v-text-field>
</template>
<script setup lang="ts">
defineProps<{
    label?: string
}>()

const menu = ref(false)
const model = defineModel<string | null>({ default: null })
</script>
