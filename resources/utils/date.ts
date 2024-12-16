import { useDate } from 'vuetify'

export const parseDate = (iso: string) => {
    return useDate().parseISO(iso)
}

export const formatDate = (iso: string) => {
    const now = new Date()
    const date = parseDate(iso)
    const sameYear = now.getFullYear() === date.getFullYear()
    return useDate().formatByString(date, sameYear ? 'd MMMM' : 'd MMMM yyyy')
}

export const parseTime = (time: string) => {
    return useDate().parse(time, 'HH:mm:ss')
}

export const formatTime = (time: string) => {
    const parsed = parseTime(time)
    if (!parsed) return 'xx:xx'
    return useDate().formatByString(parsed, 'HH:mm')
}

export const formatDuration = (start: string, stop: string) => {
    const date1 = parseTime(start)
    const date2 = parseTime(stop)

    if (!date1 || !date2) return 'xx:xx'

    let duration = useDate().getDiff(date2, date1, 'minutes')
    const h = Math.floor(duration / 60)
    duration %= 60
    const m = Math.floor(duration)

    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`
}
