import PlaylistPlayer from "../../players/PlaylistPlayer";
import HowlerPlayer from "../../players/HowlerPlayer";

export default {
    player: null,
    station: null,
    status: 'playing', // playing, stopped

    getStreamingUrl(radioStation) {
        const url =
            station.url_resolved && station.url_resolved !== ''
                ? station.url_resolved
                : station.url;

        const urlObject = new URL(url);

        if (urlObject.protocol === 'http:' || station.ssl_error) {
            urlObject.protocol = 'http:';

            const urlWithoutProtocol = urlObject.toString().replace('http://', '');

            return `${process.env.PROXY_URL}?url=${urlWithoutProtocol}`;
        }

        if (station.hls) {
            urlObject.searchParams.append('isPlaylistUrl', station.hls ? 'yes' : 'no');
        }

        return urlObject.toString();
    },
    playRadio(radioStation) {

        radioStation = (typeof radioStation == 'string') ? JSON.parse(atob(radioStation)) : radioStation;

        let streamUrl = this.getStreamingUrl(radioStation);

        if (this.player) {
            this.player.stop()
        }

        let playerInstance = null

        if (radioStation.hls) {
            playerInstance = new PlaylistPlayer(streamUrl)
        } else {
            playerInstance = new HowlerPlayer(streamUrl)
        }

        playerInstance.on('error', () => {
            alert('Failed to play.')

            this.stop()
        })

        if (playerInstance) {
            playerInstance.play()

            this.station = radioStation

            this.player = playerInstance
        }
    },

    stop() {
        if (this.player) {
            this.player.stop()
            this.player.destroy();

            this.player = null
        }
    }
}
