import { useDate as useVuetifyDate } from 'vuetify'

/**
 * Use Vuetify date utils
 * @returns Date utils
 */
export function useDate() {
    const parseDate = (iso: string) => {
        return useVuetifyDate().parseISO(iso)
    }

    const formatDate = (iso: string) => {
        const now = new Date()
        const date = parseDate(iso)
        const sameYear = now.getFullYear() === date.getFullYear()
        return useVuetifyDate().formatByString(
            date,
            sameYear ? 'd MMMM' : 'd MMMM yyyy',
        )
    }

    const parseTime = (time: string) => {
        return useVuetifyDate().parse(time, 'HH:mm:ss')
    }

    const formatDuration = (start: string, stop: string) => {
        const date1 = parseTime(start)
        const date2 = parseTime(stop)

        if (!date1 || !date2) return 'xx:xx'

        let duration = useVuetifyDate().getDiff(date2, date1, 'seconds')
        const h = Math.floor(duration / 3600)
        duration %= 3600
        const m = Math.floor(duration / 60)
        duration %= 60
        const s = Math.floor(duration)

        return `${pad(h)}:${pad(m)}${s ? ':' + pad(s) : ''}`
    }

    return { parseDate, formatDate, parseTime, formatDuration }
}
