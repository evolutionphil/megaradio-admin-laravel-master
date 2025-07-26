import BasePlayer from './BasePlayer';
import { Howl } from 'howler';

export default class HowlerPlayer extends BasePlayer {
    player: any;
    streamUrl: string;
    options: any;

    constructor(streamUrl: string, options: any = {}) {
        super(streamUrl);

        this.player = document.getElementById('player');
        this.player.src = streamUrl;

        this.setupEvents();
    }

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
        this.stop();
        this.destroyEvents();
    }

    onPlaying = () => {
        console.log(`onPlaying: ${this.player.src}`);

        this.emit('playing');
    };

    onPlay = () => {
        console.log(`onPlay: ${this.player.src}`);

        this.play();
        this.emit('play');
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
    }

    setVolume(volume: number): void {
        this.player.volume = volume;
    }
}
