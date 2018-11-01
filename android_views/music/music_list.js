import React, { Component } from 'react';
import {View,Text,Button,StyleSheet,Image,TextInput,TouchableOpacity,FlatList}  from 'react-native';

class MusicList extends Component{
    constructor(props){
        super(props);
        this.state = {
            data:[],
            loaded:false,
            input_text:'',
            fetchUrl:'http://www.developer1.cn:8001/yy.php',
        };
        this.fetchData = this.fetchData.bind(this);
        this.onchangeInput = this.onchangeInput.bind(this);
        this.renderItem = this.renderItem.bind(this);
        this.getInput = this.getInput.bind(this);
    }

    fetchData()
    {
        fetch(this.state.fetchUrl)
            .then((response) => response.json())
            .then((responseData) => {
                // 注意，这里使用了this关键字，为了保证this在调用时仍然指向当前组件，我们需要对其进行“绑定”操作
                console.log(responseData);
                this.setState({
                    data: this.state.data.concat(responseData),
                    loaded: true,
                });
            });
    }

    onchangeInput(text){
        this.setState({input_text: text});
    }

    getInput()
    {
        alert(this.state.input_text)
    }

    renderItem({item}){
        return(
            <View style={{flexDirection: 'row',marginBottom: 5}}>
                <TouchableOpacity onPress={ ()=> alert('点击了电影：'+item.title) }>
                <Image source={{uri: item.pic}} style={{width:150,height:100}}/>
                </TouchableOpacity>
                <View style={{flex:1,flexDirection:'column',justifyContent:"flex-start",alignItems: 'flex-start',marginLeft: 8,backgroundColor:"yellow"}}>
                    <TouchableOpacity  onPress={() => this.props.navigate('movieDetail',{index:item.key,title:item.title})} >
                     <Text>{item.title}</Text>
                      <Text>{item.label}</Text>
                      <Text>{item.showtime}</Text>
                      <Text>分数：{item.score}</Text>
                    </TouchableOpacity>
                </View>
            </View>
        );
    }

    _keyExtractor = (item, index) => item.key.toString();

    componentDidMount(){
        this.fetchData();
    }

    render(){
        if(!this.state.loaded){
            return(
                <View style={{flex:1,justifyContent: "center",alignItems: 'center'}}>
                    <Text>loading music...</Text>
                </View>
            );
        }


        return(
            <View style={{flex:1}}>
                <View style={{flexDirection:'row',height:40}}>
                    <TextInput  placeholder='yy..'
                                editable={true}
                                onChangeText={this.onchangeInput}
                        style={{flex:1,borderColor:'black',borderWidth: 1}}
                    />
                    <Button title='   搜索   ' onPress={this.getInput} />
                </View>

                <View style={{flexDirection:'column',alignItems:'center',marginTop:5}}>
                    <Image source={{uri:'http://img.ivsky.com/img/tupian/t/201804/29/titian-001.jpg'}} style={{width:80,height:70}} />
                    <View style={{flexDirection:'row',justifyContent:'space-between',alignSelf:'flex-start',height:80,backgroundColor: 'red'}}>
                        <View><Text>dssdds</Text></View>
                        <View><Text>111dssdds</Text></View>

                    </View>
                </View>
            </View>
        );
    }
}


export default MusicList;