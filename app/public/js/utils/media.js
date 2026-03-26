export const isImage = (filename) => {
    const extensions = ['jpg', 'jpeg', 'png']
    const ext = filename.split('.').pop().toLowerCase()
    return extensions.includes(ext)
}

export const isVideo = (filename) => {
    const extensions = ['mp4']
    const ext = filename.split('.').pop().toLowerCase()
    return extensions.includes(ext)
}