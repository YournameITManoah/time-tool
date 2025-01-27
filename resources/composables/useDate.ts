import { useDate as useVuetifyDate } from 'vuetify'

/**
 * Use Vuetify date utils
 * @returns Date utils
 */
export function useDate() {
    const dateUtils = useVuetifyDate()

    const parseDate = (iso: string) => {
        return dateUtils.parseISO(iso)
    }

    const formatDate = (iso: string) => {
        const now = new Date()
        const date = parseDate(iso)
        const sameYear = now.getFullYear() === date.getFullYear()
        return dateUtils.formatByString(
            date,
            sameYear ? 'd MMMM' : 'd MMMM yyyy',
        )
    }

    const parseTime = (time: string) => {
        if (!time) return null
        if (/^\d{2}:\d{2}$/.test(time)) {
            return dateUtils.parse(time, 'HH:mm')
        } else if (/^\d{2}:\d{2}:\d{2}$/.test(time)) {
            return dateUtils.parse(time, 'HH:mm:ss')
        } else {
            return dateUtils.parseISO(time)
        }
    }

    const formatDuration = (start: string, stop: string) => {
        const date1 = parseTime(start)
        const date2 = parseTime(stop)

        if (!date1 || !date2) return 'xx:xx'

        let duration = dateUtils.getDiff(date2, date1, 'seconds')

        console.log({ date1, date2, duration, start, stop })
        const h = Math.floor(duration / 3600)
        duration %= 3600
        const m = Math.floor(duration / 60)
        duration %= 60
        const s = Math.floor(duration)

        return `${pad(h)}:${pad(m)}${s ? ':' + pad(s) : ''}`
    }

    return { formatDate, formatDuration, parseDate, parseTime }
}
