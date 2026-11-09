<?php
// The harness disables PHP's mail transport before defining this local stub.
if (!function_exists('mail')) {
    function mail(...$arguments): bool { return true; }
}
/** Test-only HTTPS transport: route Stripe calls to the loopback simulator. */
class PaymentTestHttpsStream
{
    public $context;
    private $stream;

    public function stream_open($path, $mode, $options, &$openedPath): bool
    {
        if (parse_url($path, PHP_URL_HOST) !== 'api.stripe.com') {
            return false;
        }
        $url = getenv('PAYMENT_MOCK_URL') . parse_url($path, PHP_URL_PATH);
        $query = parse_url($path, PHP_URL_QUERY);
        if ($query) $url .= '?' . $query;
        $this->stream = @fopen($url, 'r', false, $this->context);
        return $this->stream !== false;
    }

    public function stream_read($count) { return fread($this->stream, $count); }
    public function stream_eof(): bool { return feof($this->stream); }
    public function stream_stat(): array { return []; }
    public function stream_close(): void { fclose($this->stream); }
    public function stream_set_option($option, $arg1, $arg2): bool { return false; }
}

if (in_array('https', stream_get_wrappers(), true)) {
    stream_wrapper_unregister('https');
}
stream_wrapper_register('https', PaymentTestHttpsStream::class);
