<?php

namespace tree\email;

    /**
     * Super-simple, minimum abstraction MailChimp API v3 wrapper
     * MailChimp API v3: http://developer.mailchimp.com
     * This wrapper: https://github.com/drewm/mailchimp-api
     *
     * @author Drew McLellan <drew.mclellan@gmail.com>
     * @version 2.2
     */
/**
 *
 * Examples
 *
 * Start by use-ing the class and creating an instance with your API key
 *
 * use \DrewM\MailChimp\MailChimp;
 *
 * $MailChimp = new MailChimp('abc123abc123abc123abc123abc123-us1');
 * Then, list all the mailing lists (with a get on the lists method)
 *
 * $result = $MailChimp->get('lists');
 *
 * print_r($result);
 * Subscribe someone to a list (with a post to the lists/{listID}/members method):
 *
 * $list_id = 'b1234346';
 *
 * $result = $MailChimp->post("lists/$list_id/members", [
 * 'email_address' => 'davy@example.com',
 * 'status'        => 'subscribed',
 * ]);
 *
 * print_r($result);
 * Update a list member with more information (using patch to update):
 *
 * $list_id = 'b1234346';
 * $subscriber_hash = $MailChimp->subscriberHash('davy@example.com');
 *
 * $result = $MailChimp->patch("lists/$list_id/members/$subscriber_hash", [
 * 'merge_fields' => ['FNAME'=>'Davy', 'LNAME'=>'Jones'],
 * 'interests'    => ['2s3a384h' => true],
 * ]);
 *
 * print_r($result);
 * Remove a list member using the delete method:
 *
 * $list_id = 'b1234346';
 * $subscriber_hash = $MailChimp->subscriberHash('davy@example.com');
 *
 * $MailChimp->delete("lists/$list_id/members/$subscriber_hash");
 * Quickly test for a successful action with the success() method:
 *
 * $list_id = 'b1234346';
 *
 * $result = $MailChimp->post("lists/$list_id/members", [
 * 'email_address' => 'davy@example.com',
 * 'status'        => 'subscribed',
 * ]);
 *
 * if ($MailChimp->success()) {
 * print_r($result);
 * } else {
 * echo $MailChimp->getLastError();
 * }
 * Batch Operations
 *
 * The MailChimp Batch Operations functionality enables you to complete multiple operations with a single call. A good example is adding thousands of members to a list - you can perform this in one request rather than thousands.
 *
 * use \DrewM\MailChimp\MailChimp;
 * use \DrewM\MailChimp\Batch;
 *
 * $MailChimp = new MailChimp('abc123abc123abc123abc123abc123-us1');
 * $Batch     = $MailChimp->new_batch();
 * You can then make requests on the Batch object just as you would normally with the MailChimp object. The difference is that you need to set an ID for the operation as the first argument, and also that you won't get a response. The ID is used for finding the result of this request in the combined response from the batch operation.
 *
 * $Batch->post("op1", "lists/$list_id/members", [
 * 'email_address' => 'micky@example.com',
 * 'status'        => 'subscribed',
 * ]);
 *
 * $Batch->post("op2", "lists/$list_id/members", [
 * 'email_address' => 'michael@example.com',
 * 'status'        => 'subscribed',
 * ]);
 *
 * $Batch->post("op3", "lists/$list_id/members", [
 * 'email_address' => 'peter@example.com',
 * 'status'        => 'subscribed',
 * ]);
 * Once you've finished all the requests that should be in the batch, you need to execute it.
 *
 * $result = $Batch->execute();
 * The result includes a batch ID. At a later point, you can check the status of your batch:
 *
 * $MailChimp->new_batch($batch_id);
 * $result = $Batch->check_status();
 * When your batch is finished, you can download the results from the URL given in the response. In the JSON, the result of each operation will be keyed by the ID you used as the first argument for the request.
 *
 * Webhooks
 *
 * Note: Use of the Webhooks functionality requires at least PHP 5.4.
 *
 * MailChimp webhooks enable your code to be notified of changes to lists and campaigns.
 *
 * When you set up a webhook you specify a URL on your server for the data to be sent to. This wrapper's Webhook class helps you catch that incoming webhook in a tidy way. It uses a subscription model, with your code subscribing to whichever webhook events it wants to listen for. You provide a callback function that the webhook data is passed to.
 *
 * To listen for the unsubscribe webhook:
 *
 * use \DrewM\MailChimp\Webhook;
 *
 * Webhook::subscribe('unsubscribe', function($data){
 * print_r($data);
 * });
 * At first glance the subscribe/unsubscribe looks confusing - your code is subscribing to the MailChimp unsubscribe webhook event. The callback function is passed as single argument - an associative array containing the webhook data.
 *
 * If you'd rather just catch all webhooks and deal with them yourself, you can use:
 *
 * use \DrewM\MailChimp\Webhook;
 *
 * $result = Webhook::receive();
 * print_r($result);
 * There doesn't appear to be any documentation for the content of the webhook data. It's helpful to use something like ngrok for tunneling the webhooks to your development machine - you can then use its web interface to inspect what's been sent and to replay incoming webhooks while you debug your code.
 *
 * Troubleshooting
 *
 * To get the last error returned by either the HTTP client or by the API, use getLastError():
 *
 * echo $MailChimp->getLastError();
 * For further debugging, you can inspect the headers and body of the response:
 *
 * print_r($MailChimp->getLastResponse());
 * If you suspect you're sending data in the wrong format, you can look at what was sent to MailChimp by the wrapper:
 *
 * print_r($MailChimp->getLastRequest());
 */
class MailChimp
{
    private $api_key;
    private $api_endpoint = 'https://<dc>.api.mailchimp.com/3.0';

    /*  SSL Verification
        Read before disabling:
        http://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/
    */
    public $verify_ssl = true;

    private $request_successful = false;
    private $last_error = '';
    private $last_response = array();
    private $last_request = array();

    /**
     * Create a new instance
     * @param string $api_key Your MailChimp API key
     * @throws \Exception
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;

        if (strpos($this->api_key, '-') === false) {
            throw new \Exception("Invalid MailChimp API key `{$api_key}` supplied.");
        }

        list(, $data_center) = explode('-', $this->api_key);
        $this->api_endpoint = str_replace('<dc>', $data_center, $this->api_endpoint);

        $this->last_response = array('headers' => null, 'body' => null);
    }

    /**
     * Create a new instance of a Batch request. Optionally with the ID of an existing batch.
     * @param string $batch_id Optional ID of an existing batch, if you need to check its status for example.
     * @return Batch            New Batch object.
     */
    public function new_batch($batch_id = null)
    {
        return new Batch($this, $batch_id);
    }

    /**
     * Convert an email address into a 'subscriber hash' for identifying the subscriber in a method URL
     * @param   string $email The subscriber's email address
     * @return  string          Hashed version of the input
     */
    public function subscriberHash($email)
    {
        return md5(strtolower($email));
    }

    /**
     * Was the last request successful?
     * @return bool  True for success, false for failure
     */
    public function success()
    {
        return $this->request_successful;
    }

    /**
     * Get the last error returned by either the network transport, or by the API.
     * If something didn't work, this should contain the string describing the problem.
     * @return  array|false  describing the error
     */
    public function getLastError()
    {
        return $this->last_error ?: false;
    }

    /**
     * Get an array containing the HTTP headers and the body of the API response.
     * @return array  Assoc array with keys 'headers' and 'body'
     */
    public function getLastResponse()
    {
        return $this->last_response;
    }

    /**
     * Get an array containing the HTTP headers and the body of the API request.
     * @return array  Assoc array
     */
    public function getLastRequest()
    {
        return $this->last_request;
    }

    /**
     * Make an HTTP DELETE request - for deleting data
     * @param   string $method URL of the API request method
     * @param   array $args Assoc array of arguments (if any)
     * @param   int $timeout Timeout limit for request in seconds
     * @return  array|false   Assoc array of API response, decoded from JSON
     */
    public function delete($method, $args = array(), $timeout = 10)
    {
        return $this->makeRequest('delete', $method, $args, $timeout);
    }

    /**
     * Make an HTTP GET request - for retrieving data
     * @param   string $method URL of the API request method
     * @param   array $args Assoc array of arguments (usually your data)
     * @param   int $timeout Timeout limit for request in seconds
     * @return  array|false   Assoc array of API response, decoded from JSON
     */
    public function get($method, $args = array(), $timeout = 10)
    {
        return $this->makeRequest('get', $method, $args, $timeout);
    }

    /**
     * Make an HTTP PATCH request - for performing partial updates
     * @param   string $method URL of the API request method
     * @param   array $args Assoc array of arguments (usually your data)
     * @param   int $timeout Timeout limit for request in seconds
     * @return  array|false   Assoc array of API response, decoded from JSON
     */
    public function patch($method, $args = array(), $timeout = 10)
    {
        return $this->makeRequest('patch', $method, $args, $timeout);
    }

    /**
     * Make an HTTP POST request - for creating and updating items
     * @param   string $method URL of the API request method
     * @param   array $args Assoc array of arguments (usually your data)
     * @param   int $timeout Timeout limit for request in seconds
     * @return  array|false   Assoc array of API response, decoded from JSON
     */
    public function post($method, $args = array(), $timeout = 10)
    {
        return $this->makeRequest('post', $method, $args, $timeout);
    }

    /**
     * Make an HTTP PUT request - for creating new items
     * @param   string $method URL of the API request method
     * @param   array $args Assoc array of arguments (usually your data)
     * @param   int $timeout Timeout limit for request in seconds
     * @return  array|false   Assoc array of API response, decoded from JSON
     */
    public function put($method, $args = array(), $timeout = 10)
    {
        return $this->makeRequest('put', $method, $args, $timeout);
    }

    /**
     * Performs the underlying HTTP request. Not very exciting.
     * @param  string $http_verb The HTTP verb to use: get, post, put, patch, delete
     * @param  string $method The API method to be called
     * @param  array $args Assoc array of parameters to be passed
     * @param int $timeout
     * @return array|false Assoc array of decoded result
     * @throws \Exception
     */
    private function makeRequest($http_verb, $method, $args = array(), $timeout = 10)
    {
        if (!function_exists('curl_init') || !function_exists('curl_setopt')) {
            throw new \Exception("cURL support is required, but can't be found.");
        }

        $url = $this->api_endpoint . '/' . $method;

        $this->last_error = '';
        $this->request_successful = false;
        $response = array('headers' => null, 'body' => null);
        $this->last_response = $response;

        $this->last_request = array(
            'method' => $http_verb,
            'path' => $method,
            'url' => $url,
            'body' => '',
            'timeout' => $timeout,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.api+json',
            'Content-Type: application/vnd.api+json',
            'Authorization: apikey ' . $this->api_key
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'DrewM/MailChimp-API/3.0 (github.com/drewm/mailchimp-api)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        switch ($http_verb) {
            case 'post':
                curl_setopt($ch, CURLOPT_POST, true);
                $this->attachRequestPayload($ch, $args);
                break;

            case 'get':
                $query = http_build_query($args, '', '&');
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $query);
                break;

            case 'delete':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            case 'patch':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                $this->attachRequestPayload($ch, $args);
                break;

            case 'put':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                $this->attachRequestPayload($ch, $args);
                break;
        }

        $response['body'] = curl_exec($ch);
        $response['headers'] = curl_getinfo($ch);

        if (isset($response['headers']['request_header'])) {
            $this->last_request['headers'] = $response['headers']['request_header'];
        }

        if ($response['body'] === false) {
            $this->last_error = curl_error($ch);
        }

        curl_close($ch);

        $formattedResponse = $this->formatResponse($response);

        $this->determineSuccess($response, $formattedResponse);

        return $formattedResponse;
    }

    /**
     * @return string The url to the API endpoint
     */
    public function getApiEndpoint()
    {
        return $this->api_endpoint;
    }

    /**
     * Encode the data and attach it to the request
     * @param   resource $ch cURL session handle, used by reference
     * @param   array $data Assoc array of data to attach
     */
    private function attachRequestPayload(&$ch, $data)
    {
        $encoded = json_encode($data);
        $this->last_request['body'] = $encoded;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded);
    }

    /**
     * Decode the response and format any error messages for debugging
     * @param array $response The response from the curl request
     * @return array|false    The JSON decoded into an array
     */
    private function formatResponse($response)
    {
        $this->last_response = $response;

        if (!empty($response['body'])) {
            return json_decode($response['body'], true);
        }

        return false;
    }

    /**
     * Check if the response was successful or a failure. If it failed, store the error.
     * @param array $response The response from the curl request
     * @param array|false $formattedResponse The response body payload from the curl request
     * @return bool     If the request was successful
     */
    private function determineSuccess($response, $formattedResponse)
    {
        $status = $this->findHTTPStatus($response, $formattedResponse);

        if ($status >= 200 && $status <= 299) {
            $this->request_successful = true;
            return true;
        }

        if (isset($formattedResponse['detail'])) {
            $this->last_error = sprintf('%d: %s', $formattedResponse['status'], $formattedResponse['detail']);
            return false;
        }

        $this->last_error = 'Unknown error, call getLastResponse() to find out what happened.';
        return false;
    }

    /**
     * Find the HTTP status code from the headers or API response body
     * @param array $response The response from the curl request
     * @param array|false $formattedResponse The response body payload from the curl request
     * @return int  HTTP status code
     */
    private function findHTTPStatus($response, $formattedResponse)
    {
        if (!empty($response['headers']) && isset($response['headers']['http_code'])) {
            return (int)$response['headers']['http_code'];
        }

        if (!empty($response['body']) && isset($formattedResponse['status'])) {
            return (int)$formattedResponse['status'];
        }

        return 418;
    }
}
