using System;
using System.Collections;
using System.Collections.Generic;
using System.Net.Sockets;
using System.Text;
using System.Threading;
using UnityEngine;

public class ClientConnection : MonoBehaviour {
    private TcpClient socket;
    private Thread clientReceiveThread;

	// Use this for initialization
	void Start () {
		ConnectToServer();
	}

	// Update is called once per frame
	void Update () {
        if(Input.GetKeyDown(KeyCode.Space)) {
            SendMessage();
        }
	}

    // Setup socket connection
    private void ConnectToServer() {
        try {
            clientReceiveThread = new Thread(new ThreadStart(ListenForData));
            clientReceiveThread.IsBackground = true;
            clientReceiveThread.Start();
        } catch(Exception e) {
            Debug.Log("On client connect exception " + e);
        }
    }

    private void ListenForData() {
        try {
            socket = new TcpClient("192.168.57.3", 2000);
            Byte[] bytes = new Byte[1024];
            while(true) {
                using(NetworkStream stream = socket.GetStream()) {
                    int length;

                    while((length = stream.Read(bytes, 0, bytes.Length)) != 0) {
                        var incomingData = new byte[length];
                        Array.Copy(bytes, 0, incomingData, 0, length);
                        string serverMessage = Encoding.ASCII.GetString(incomingData);
                        Debug.Log("Server message: " + serverMessage);
                    }
                }
            }
        } catch(SocketException e) {
            Debug.Log("Socket exception: " + e);
        }
    }

    private void SendMessage() {
        if(socket == null) {
            return;
        }
        try {
            NetworkStream stream = socket.GetStream();
            if(stream.CanWrite) {
                string clientMessage = "Message from client";
                byte[] clientMessageAsBytes = Encoding.ASCII.GetBytes(clientMessage);
                stream.Write(clientMessageAsBytes, 0, clientMessageAsBytes.Length);
                Debug.Log("Client sent message");
            }
        } catch(SocketException e) {
            Debug.Log("Socket exception: " + e);
        }
    }
}
