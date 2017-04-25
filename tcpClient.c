#include<stdio.h>  
#include<sys/socket.h>  
#include<netinet/in.h>  
#include<stdlib.h>  
#include<memory.h>  
#include<string.h>  
#include<arpa/inet.h> //inet_ntoa  function
#include<unistd.h> // close function
  
#define PORT 8888  
int main(int argc,char **argv){  
    int sock;  
    struct sockaddr_in my_addr;  
    int len;  
    char buf[100];  
    if(argc <3){  
        printf("Usage: %s <ip> <port> <message>",argv[0]);  
        exit(1);  
    }  
  
    if((sock=socket(AF_INET,SOCK_STREAM,0))<0){  
        printf("socket create error!\n");  
        exit(1);  
    }  
    my_addr.sin_family=AF_INET;  
    my_addr.sin_port=htons(8888);  
    if(inet_aton(argv[1],(struct in_addr *)&my_addr.sin_addr.s_addr) == 0){  
        printf("%s chage error!\n",argv[1]);  
        exit(1);  
    }  
    if(connect(sock,(struct sockaddr*)&my_addr,sizeof(struct sockaddr))<0){  
        printf("connect error!\n");  
        exit(1);  
    }  
    printf("connected!\n");  
  
    while(1){  
        memset(buf,0,100);  
        len=recv(sock,buf,100,0);  
        if(len<0){  
            printf("recv error!\n");  
            exit(1);  
        }else if(len == 0){  
            printf("the client quit!\n");  
            exit(1);  
        }else{  
            printf("receive message: %s \n",buf);  
        }  
        printf("-------------------------\n");  
        memset(buf,0,100);  
        printf("Input message to send: ");  
        fgets(buf,100,stdin);  
        len=send(sock,buf,strlen(buf)-1,0);  
        if(len<0){  
            printf("send error!\n");  
            exit(1);  
        }else{  
            printf("send succeed! send : %s\n",buf);  
        }  
    }  
    close(sock);  
}