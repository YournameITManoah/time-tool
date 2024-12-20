export const formatTime = (time?: string) =>
    time?.replace(/(\d{2}:\d{2}):00/g, '$1')
