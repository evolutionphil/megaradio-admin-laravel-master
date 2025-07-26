export default abstract class BasePlayer {
    player: any;
    streamUrl: string;
    private events;

    constructor(streamUrl: string) {
        this.events = {};
        this.streamUrl = streamUrl;
    }

    abstract playing(): boolean;

    abstract play(): void;

    abstract stop(): void;

    abstract pause(): void;

    abstract resume(): void;

    abstract destroy(): void;

    getOriginalStreamUrl() {
        return this.streamUrl.includes('url') ? atob(this.streamUrl.split('url')[1].substring(1)) : this.streamUrl;
    }

    on(type, listener) {
        this.events[type] = this.events[type] || [];
        this.events[type].push(listener);
    }

    emit(type, arg = null) {
        if (this.events[type]) {
            this.events[type].forEach((listener) => listener(arg));
        }
    }
}
