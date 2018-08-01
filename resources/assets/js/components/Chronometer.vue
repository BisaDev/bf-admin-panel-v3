<template>
    <div>
        <span id="time" v-html="time"></span>
        <input type="hidden" name="time" :value="this.minutes">
    </div>
</template>

<script>
    export default {
        props: ['availableTime'],
        data: function() {
            return {
                state: "started",
                startTime: Date.now(),
                currentTime: Date.now(),
                interval: null
            }
        },
        mounted: function() {
            this.interval = setInterval(this.updateCurrentTime, 1000);
        },
        destroyed: function() {
            clearInterval(this.interval)
        },
        computed: {
            time: function() {
                return this.minutes + ' min / ' + this.availableTime + ' min';
            },
            milliseconds: function() {
                return this.currentTime - this.$data.startTime;
            },
            minutes: function() {
                var lapsed = this.milliseconds;
                var min = Math.floor((lapsed / 1000 / 60) % 60);
                return min >= 10 ? min : '0' + min;
            },
        },
        methods: {
            reset: function() {
                this.$data.state = "started";
                this.$data.startTime = Date.now();
                this.$data.currentTime = Date.now();
            },
            pause: function() {
                this.$data.state = "paused";
            },
            resume: function() {
                this.$data.state = "started";
            },
            updateCurrentTime: function() {
                if (this.$data.state == "started") {
                    this.currentTime = Date.now();
                }
            }
        }
    }
</script>