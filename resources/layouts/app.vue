<template>
    <v-app :class="{ 'bg-grey-lighten-4': !current.dark }">
        <v-sonner
            position="top-right"
            expand
            :container-aria-label="i18n.t('Notifications')"
        />
        <slot name="header" />
        <v-main scrollable>
            <slot />
        </v-main>
        <slot name="footer" />
    </v-app>
</template>
<script setup lang="ts">
import { useTheme } from '@/composables/useTheme'
import { useGeneralStore } from '@/stores/general'
import { storeToRefs } from 'pinia'
import { toast, VSonner } from 'vuetify-sonner'
import 'vuetify-sonner/style.css'

defineOptions({ name: 'AppLayout' })

// When notifications change, display them
const flash = useProperty('flash')
watchImmediate(flash, (val) => {
    if (val) {
        Object.keys(val).forEach((key) => {
            const msg = val[key as keyof typeof val]
            if (msg) toast(msg, { cardProps: { color: key } })
        })
    }
})

// Update internal locale
const i18n = useI18n()
const locale = useProperty('locale')
watchImmediate(locale, (val) => {
    i18n.locale.value = val
})

// Update internal theme
const { theme } = storeToRefs(useGeneralStore())
const { current, prefersDark, setTheme } = useTheme()

watchImmediate(prefersDark, () => {
    setTheme(theme.value)
})

watchImmediate(theme, (val) => {
    setTheme(val)
})
</script>
