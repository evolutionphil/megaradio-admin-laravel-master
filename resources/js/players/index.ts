import PlaylistPlayer from "./PlaylistPlayer";
import HowlerPlayer from "./HowlerPlayer";

export default {
    getEffectiveStreamingUrl(streamUrl) {
        const isPlaylistUrl =
            streamUrl.includes('.m3u') || streamUrl.includes('.ts') ? 'yes' : 'no';

        if (streamUrl?.startsWith('http://')) {
            const encodedUrl = btoa(streamUrl);

            return `https://proxy.megaradio.live?url=${encodedUrl}&isPlaylistUrl=${isPlaylistUrl}`;
        }

        return `${streamUrl}?isPlaylistUrl=${isPlaylistUrl}`;

    },

    play(streamUrl) {
        const isPlaylistUrl = streamUrl.includes('isPlaylistUrl=yes')

        const actualStreamUrl = this.getEffectiveStreamingUrl(streamUrl)

        let playerInstance = null

        if (isPlaylistUrl) {
            playerInstance = new PlaylistPlayer(actualStreamUrl)
        } else {
            playerInstance = new HowlerPlayer(actualStreamUrl)
        }

        if (playerInstance) {
            playerInstance.play()
        }else {
            throw new Error("Failed to play.")
        }


        playerInstance.on('error', () => {
            alert('Failed to play.')
        })
    },

    stop() {

    }
}
