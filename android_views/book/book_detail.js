import React,{ Component } from 'react';
import {View,Text,Button,StyleSheet,Image,FlatList,TouchableOpacity} from 'react-native';
import {createBottomTabNavigator,createStackNavigator,createDrawerNavigator} from 'react-navigation';


class BookDetail extends  Component {
    // 配置页面导航选项
    static navigationOptions = ({navigation}) => ({
        title: ` ${navigation.state.params.title}`,       //前一个页面传来的对象的name属性
    })



    constructor(props){
        super(props);
        this.state={
          data:[],
          loaded:false,
          href:this.props.navigation.state.params.href,
        };
        this.fetchData= this.fetchData.bind(this);

    }

    fetchData(){
        let url = this.state.href;
        fetch('http://www.developer1.cn:8001/api.php?a=1')
            .then((response) => response.json())
            .then((responseData) => {
                // 注意，这里使用了this关键字，为了保证this在调用时仍然指向当前组件，我们需要对其进行“绑定”操作
                console.log(responseData);
                this.setState({
                    data: responseData,
                    loaded: true,
                });
            });
    }


    componentDidMount(){
        this.fetchData();

    }

    render(){
            if(!this.state.loaded) {
                return (
                    <View style={{
                        flex: 1,
                        flexDirection: "row",
                        justifyContent: "center",
                        alignItems: 'center',
                        backgroundColor: "#F5FCFF"
                    }}>
                        <Text>图书详情...{this.props.navigation.state.params.href}</Text>
                    </View>
                );
            }

            let book = this.state.data;
            alert(book.title)

            return(
                <View style={{flex:1}}>
                    <View style={{flexDirection:'row',height:150,backgroundColor:'red'}}>
                        <View style={{width:180,height:150}}>
                            <Image source={{uri:'https://img3.doubanio.com/view/subject/l/public/s1727290.jpg'}} style={{width:180,height:150}}/>
                        </View>
                        <View style={{justifyContent:"space-around",paddingLeft: 20}}>
                            <Text>2222</Text>
                            <Text>2222</Text>
                            <Text>2222</Text>
                        </View>
                    </View>

                    <View>
                    <Text>{this.props.navigation.state.params.title}</Text>
                    </View>
                </View>
            );


    }

    renderData({item}){
        return(
        <View style={{flexDirection: 'row',height:100,marginBottom: 5}}>
            <View style={{width:150,height:100,backgroundColor:'red'}}>
                <TouchableOpacity  onPress={() => {alert('点击了图书，标题:'+item.title)}} >
                <Image source={{uri:item.src}}  style={{width:150,height:100}} />
                </TouchableOpacity>
            </View>
            <View style={{flex:1,height:100,justifyContent:'center',backgroundColor:'skyblue'}}>
                <TouchableOpacity  onPress={() => {alert('点击了图书，标题:'+item.title)}} >
                <Text style={{textAlign: 'center'}}>标题：{item.title}</Text>
                <Text style={{textAlign:'center'}}>出版社：人民大学出版社  作者：高洛峰</Text>
                <Text style={{textAlign:'center'}}>价格：350，页数：00页</Text>
                </TouchableOpacity>
            </View>

        </View>
        );
    }
}



const styles = StyleSheet.create({
    box1:{
        width:50,height:50
    }
    }

);

export default BookDetail;