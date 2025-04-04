console.log(!window.FFmpeg);

const { createFFmpeg, fetchFile } = FFmpeg; // FFmpeg được tải từ CDN

const ffmpeg = createFFmpeg({
    log: true,
    corePath: "https://unpkg.com/@ffmpeg/core@0.12.10/dist/esm/ffmpeg-core.js", // Đường dẫn tới FFmpeg core
});

async function bannerHome() {
    const video = document.getElementById("banner-video");
    if (!video) return;

    const dataSrc = video.getAttribute("data-src");

    // Load FFmpeg nếu chưa load
    if (!ffmpeg.isLoaded()) {
        await ffmpeg.load();
    }

    try {
        // Tải video từ data-src
        const response = await fetch(dataSrc);
        const videoData = await response.arrayBuffer();

        // Ghi file vào hệ thống file ảo của FFmpeg
        ffmpeg.FS("writeFile", "input.mp4", new Uint8Array(videoData));

        // Lấy thông tin duration của video
        const duration = await getVideoDuration(dataSrc);
        const chunkDuration = 10; // Mỗi đoạn 10 giây
        const chunks = [];

        // Chia nhỏ video
        for (let start = 0; start < duration; start += chunkDuration) {
            const outputName = `chunk_${start}.mp4`;

            await ffmpeg.run(
                "-i",
                "input.mp4",
                "-ss",
                `${start}`, // Thời gian bắt đầu
                "-t",
                `${chunkDuration}`, // Độ dài đoạn
                "-c:v",
                "libx264", // Codec video H.264
                "-c:a",
                "aac", // Codec audio AAC
                "-f",
                "mp4", // Định dạng MP4
                outputName
            );

            // Đọc file đầu ra
            const data = ffmpeg.FS("readFile", outputName);
            const blob = new Blob([data.buffer], { type: "video/mp4" });
            chunks.push(blob);

            // Xóa file tạm
            ffmpeg.FS("unlink", outputName);
        }

        // Hiển thị hoặc xử lý các đoạn video
        chunks.forEach((chunk, index) => {
            const url = URL.createObjectURL(chunk);
            console.log(`Chunk ${index}: ${url}`);

            // Gán đoạn đầu tiên vào video element để phát (tùy chọn)
            if (index === 0) {
                video.src = url;
            }

            // Tạo video element để kiểm tra (tùy chọn)
            const chunkVideo = document.createElement("video");
            chunkVideo.src = url;
            chunkVideo.controls = true;
            document.body.appendChild(chunkVideo);
        });
    } catch (error) {
        console.error("Lỗi khi xử lý video:", error);
    }
}

// Hàm phụ để lấy duration
async function getVideoDuration(url) {
    const tempVideo = document.createElement("video");
    tempVideo.src = url;
    await new Promise((resolve) => (tempVideo.onloadedmetadata = resolve));
    return tempVideo.duration;
}

export default bannerHome();
