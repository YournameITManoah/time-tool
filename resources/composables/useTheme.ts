import type { Theme } from '@/types'

import { useTheme as useVuetifyTheme } from 'vuetify'

export function useTheme() {
    const vuetifyTheme = useVuetifyTheme()
    const prefersDark = usePreferredDark()

    const setTheme = (theme: Theme) => {
        if (theme == 'system') {
            vuetifyTheme.global.name.value = prefersDark.value
                ? 'dark'
                : 'light'
        } else {
            vuetifyTheme.global.name.value = theme
        }
    }

    return { current: vuetifyTheme.current, prefersDark, setTheme }
}
