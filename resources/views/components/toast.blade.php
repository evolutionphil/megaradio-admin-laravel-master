<!-- Notifications -->
<div x-data="{
        notifications: [],
        displayDuration: 8000,

        addNotification({ variant = 'info', title = null, message = null}) {
            const id = Date.now()

            const defaultTitles = {
                success: 'Success',
                info: 'Info',
                warning: 'Warning',
                error: 'Something went wrong.',
            }

            const notification = { id, variant, title: title || defaultTitles[variant], message }

            // Keep only the most recent 20 notifications
            if (this.notifications.length >= 20) {
                this.notifications.splice(0, this.notifications.length - 19)
            }

            // Add the new notification to the notifications stack
            this.notifications.push(notification)

        },
        removeNotification(id) {
            setTimeout(() => {
                this.notifications = this.notifications.filter(
                    (notification) => notification.id !== id,
                )
            }, 400);
        },
    }" x-on:notify.window="addNotification({
            variant: $event.detail.variant,
            title: $event.detail.title,
            message: $event.detail.message,
})">
    <div x-on:mouseenter="$dispatch('pause-auto-dismiss')"
         x-on:mouseleave="$dispatch('resume-auto-dismiss')"
         class="group pointer-events-none fixed inset-x-8 top-0 z-30 flex min-w-[400px] max-w-full flex-col gap-2 bg-transparent px-6 py-6 md:bottom-0 md:left-[unset] md:right-0 md:top-[unset] md:max-w-sm">
        <template x-for="(notification, index) in notifications"
                  x-bind:key="notification.id">
            <!-- root div holds all of the notifications  -->
            <div>
                <!-- Info Notification  -->
                <template x-if="notification.variant === 'info'">
                    <x-toasts.info />
                </template>
                <template x-if="notification.variant === 'success'">
                    <x-toasts.success />
                </template>
                <template x-if="notification.variant === 'warning'">
                    <x-toasts.warning />
                </template>
                <template x-if="notification.variant === 'error'">
                    <x-toasts.error />
                </template>
            </div>
        </template>
    </div>
</div>
