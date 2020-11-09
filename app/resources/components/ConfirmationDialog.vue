<template>
    <div class="flash-message flash-message-active">
        <div class="centralize-wrapper">
            <div class="centralize-inner">
                <div class="centralize-content flash-confirmation">
                    <div class="flash-removable">
                        <button type="button" @click="conCancellation" class="close flash-close" aria-hidden="true">×</button>
                        <div class="flash-icon"></div>
                        <p>{{ messages['title'] }}</p>
                        <a class="hidden-flash-item btn btn-sm btn-info btn-flat" @click="onConfirmation" href="javascript:">{{ messages['confirmButtonText'] }}</a>
                        <a class="hidden-flash-item btn btn-sm btn-warning btn-flat"
                           @click="conCancellation" href="javascript:">{{ messages['cancelButtonText'] }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['messages'],
        methods: {
            onConfirmation(){
                this.$emit("confirm");
            },
            conCancellation(){
                this.$emit("cancel");
            }
        }
    }
</script>
