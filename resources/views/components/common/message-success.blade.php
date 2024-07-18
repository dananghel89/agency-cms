@props(['type' => 'default', 'on' => false, 'multiple' => false, 'message' => null])

<x-common.message-item :type="$type" :on="$on" :multiple="$multiple" :message="$message" class="border-left-success" />
