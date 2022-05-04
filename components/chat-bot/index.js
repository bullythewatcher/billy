const tmi = require('tmi.js');
const axios = require('axios').default;
const config = require('./config/config.json');

const client = new tmi.Client({
    options: { debug: true },
    connection: {
        reconnect: true,
        secure: true,
        rejoin: true
    },
    identity: {
        username: config.username,
        password: config.password
    },
    channels: config.channels
});

// Register our event handlers (defined below)
client.on('message', onMessageHandler);
client.on('connected', onConnectedHandler);

// Connect to Twitch:
client.connect();

// Called every time a message comes in
function onMessageHandler (target, context, msg, self) {
    if (self) { return; } // Ignore messages from the bot

    // Remove whitespace from chat message
    const message = msg.trim();

    const num = processChat(context.id, target, context.username, message);
    console.log('message-id', context.id)
    console.log('target', target)
    console.log('context.username', context.username)
    console.log('message', message)
    /*
    if (commandName === '!dice') {

        client.say(target, `You rolled a ${num}`);
        console.log(`* Executed ${commandName} command`);
    } else {
        console.log(`* Unknown command ${context.username} ${commandName}`);
    }
    */

}

function processChat (message_id, target, username, message) {

    const response = axios({
        url: config.rabbitmq,
        method: 'POST',
        timeout: 60000,
        data: {
            chat_id: message_id,
            chat_target: target,
            chat_username: username,
            chat_msg: message
        },
        responseType: 'json',
        json: true
    })

}

// Called every time the bot connects to Twitch chat
function onConnectedHandler (addr, port) {
    console.log(`* Connected to ${addr}:${port}`);
}