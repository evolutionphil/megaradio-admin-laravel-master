import BasePlayer from './BasePlayer';
import Hls from "hls.js";

export default class PlaylistPlayer extends BasePlayer {
    player: any;
    streamUrl: string;
    hls: Hls;
    options: any;

    constructor(streamUrl: string, options: any = {}) {
        super(streamUrl);

        this.player = document.getElementById('player');
        this.player.src = streamUrl;

        this.setupEvents();

        if (Hls.isSupported()) {
            const hls = new Hls();
            hls.loadSource(streamUrl);
            hls.attachMedia(this.player);

            hls.on(Hls.Events.FRAG_CHANGED, this.onFragChanged);

            hls.on(Hls.Events.ERROR, this.onHlsError);

            this.hls = hls;
        }
    }

    onHlsError = (event, data) => {
        this.emit('error');

        console.log('PlaylistPlayer: Error');
    };

    onFragChanged = (event, data) => {
        if (data['title']) {
            let title = data['title'];
            console.log('onFragChanged: ' + title);
        }
    };

    playing(): boolean {
        return this.player && this.player.duration > 0 && !this.player.paused;
    }

    play(): void {
        this.player.play();
    }

    stop(): void {
        this.player.pause();
    }

    pause(): void {
        this.player.pause();
    }

    resume(): void {
        this.player.play();
    }

    destroy(): void {
        this.destroyEvents();
        this.hls.destroy();
    }

    onPlaying = () => {
        console.log(`onPlaying: ${this.player.src}`);

        this.emit('playing');
    };

    onPlay = () => {
        console.log(`onPlay: ${this.player.src}`);

        this.emit('play');
        this.play();
    };

    onPause = () => {
        console.log(`onPause: ${this.player.src}`);

        this.emit('pause');
    };

    onError = () => {
        this.emit('error');
    };

    setupEvents(): void {
        console.log('setupEvents');

        this.player.addEventListener('playing', this.onPlaying);
        this.player.addEventListener('play', this.onPlay);
        this.player.addEventListener('pause', this.onPause);
        this.player.addEventListener('error', this.onError);
    }

    destroyEvents(): void {
        console.log('destroyEvents');

        this.player.removeEventListener('playing', this.onPlaying);
        this.player.removeEventListener('play', this.onPlay);
        this.player.removeEventListener('pause', this.onPause);
        this.player.removeEventListener('error', this.onError);

        if (this.hls) {
            this.hls.off(Hls.Events.FRAG_CHANGED, this.onFragChanged);
            this.hls.off(Hls.Events.ERROR, this.onHlsError);
        }
    }

    setVolume(volume: number): void {
        this.player.volume = volume;
    }
}
