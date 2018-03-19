<template>
    <div :class="classes"
         style="right: 25px; bottom: 25px;"
         role="alert"
         v-show="show"
         v-text="body">
    </div>
</template>

<script>
    export default {
        props: {
            message: {
                type: String
            },

            messageLevel: {
                type: String
            }
        },

        data() {
            return {
                body: this.message,
                level: this.messageLevel || 'success',
                show: false
            }
        },

        computed: {
            classes() {
                let defaults = ['position-fixed', 'p-4', 'border', 'text-white'];

                if (this.level === 'success') defaults.push('bg-success', 'border-success');
                if (this.level === 'warning') defaults.push('bg-warning', 'border-warning');
                if (this.level === 'danger') defaults.push('bg-danger', 'border-danger');

                return defaults;
            }
        },

        created() {
            if (this.message) {
                this.flash();
            }

            window.events.$on(
                'flash', data => this.flash(data)
            );
        },

        methods: {
            flash(data) {
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                }

                this.show = true;

                this.hide();
            },

            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>
