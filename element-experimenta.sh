#!/bin/bash

# Your Matrix account details
USERNAME="@yasuakikudo:matrix.org"
PASSWORD=""
SERVER_URL="https://matrix.org"

# Login endpoint
LOGIN_ENDPOINT="${SERVER_URL}/_matrix/client/r0/login"

# Login payload
LOGIN_PAYLOAD=$(cat <<EOF
{
    "type": "m.login.password",
    "user": "$USERNAME",
    "password": "$PASSWORD"
}
EOF
)

# Perform the login request
RESPONSE=$(curl -s -X POST -H "Content-Type: application/json" -d "$LOGIN_PAYLOAD" "$LOGIN_ENDPOINT")

# Extract the access token
ACCESS_TOKEN=$(echo $RESPONSE | jq -r '.access_token')

if [ "$ACCESS_TOKEN" != "null" ]; then
    echo "Access Token: $ACCESS_TOKEN"
    
    # Room ID and Message
    ROOM_ID="!YnyCIhIXTzxLtdOjGQ:matrix.org"
    MESSAGE="Hello from bash script!"

    # Generate a unique transaction ID
    TXN_ID=$(date +%s)

    # Endpoint for sending message
    SEND_MSG_ENDPOINT="${SERVER_URL}/_matrix/client/r0/rooms/${ROOM_ID}/send/m.room.message/${TXN_ID}?access_token=${ACCESS_TOKEN}"

    # Message payload
    MESSAGE_PAYLOAD=$(cat <<EOF
{
    "msgtype": "m.text",
    "body": "$MESSAGE"
}
EOF
    )

    # Send the message
    SEND_RESPONSE=$(curl -s -X PUT -H "Content-Type: application/json" -d "$MESSAGE_PAYLOAD" "$SEND_MSG_ENDPOINT")

    echo "Message sent. Response: $SEND_RESPONSE"
else
    echo "Failed to log in. Please check your credentials."
fi
