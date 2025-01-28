<template>
    <v-menu v-if="currentTheme">
        <template #activator="{ props: menuProps }">
            <v-btn
                v-if="variant === 'button'"
                variant="text"
                :rounded="100"
                v-bind="menuProps"
            >
                <v-icon size="large" :icon="getThemeIcon(currentTheme.value)" />
                <v-tooltip
                    activator="parent"
                    :text="currentTheme.title"
                    location="top"
                />
            </v-btn>
            <v-list-item
                v-else
                :prepend-icon="getThemeIcon(currentTheme.value)"
                :title="currentTheme.title"
                v-bind="menuProps"
            />
        </template>
        <v-list>
            <template v-for="item in themes" :key="item.value">
                <v-list-item
                    v-if="currentTheme.value !== item.value"
                    :title="item.title"
                    :prepend-icon="getThemeIcon(item.value)"
                    @click="theme = item.value"
                />
            </template>
        </v-list>
    </v-menu>
</template>
<script setup lang="ts">
import type { Theme } from '@/types'

import { useGeneralStore } from '@/stores/general'
import { storeToRefs } from 'pinia'

const { t } = useI18n()
const { theme } = storeToRefs(useGeneralStore())

const themes = computed((): { title: string; value: Theme }[] => {
    return [
        { title: t('Light theme'), value: 'light' },
        { title: t('Dark theme'), value: 'dark' },
        { title: t('System theme'), value: 'system' },
    ]
})

defineProps<{
    variant: 'button' | 'list'
}>()

const currentTheme = useArrayFind(themes, (t) => t.value === theme.value)

const getThemeIcon = (theme: Theme) => {
    switch (theme) {
        case 'dark':
            return 'mdi-weather-night'
        case 'light':
            return 'mdi-white-balance-sunny'
        case 'system':
            return 'mdi-monitor'
    }
}
</script>
