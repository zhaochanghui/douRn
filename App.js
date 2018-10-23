/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, {Component} from 'react';
import {Platform, StyleSheet, Text, View,Image,Button} from 'react-native';
import {TabNavigator,createBottomTabNavigator,createStackNavigator,createSwitchNavigator}  from 'react-navigation';

import BookList from './android_views/book/book_list';
import BookDetail from './android_views/book/book_detail';

const instructions = Platform.select({
  ios: 'Press Cmd+R to reload,\n' + 'Cmd+D or shake for dev menu',
  android:
    'Double tap R on your keyboard to reload,\n' +
    'Shake or press menu button for dev menu',
});

class Show extends Component{

    render(){
        return(
            <View style={{flex:1,justifyContent:'center',alignItems:'center',backgroundColor: '#F5FCFF'}}>
                <Text style={{fontSize:20,textAlign:'center'}}>{this.props.desc}</Text>
            </View>
        );
    }
}



class Book extends Component{
    static navigationOptions={
        title:'图书',
        tabBarIcon: ({}) => (
            <Image
                source={{uri:'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=3249694114,25894522&fm=26&gp=0.jpg'}}
                style={[{height: 24, width: 24}, {}]}
            />
        ),
    };
    render() {
        return (
            <BookList navigate={this.props.navigation.navigate} />
        );
    };
}


class Movie extends Component{
    static navigationOptions={
        title:'电影',
        tabBarIcon:({})=>(
            <Image source={{uri:'https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=2823951946,1544725307&fm=26&gp=0.jpg'}}
                   style={[{height:24,width:25},{}]}
            />
        ),
    };
    render(){
        const { navigate } = this.props.navigation;
        return(
            <View style={{flex:1}}>
                <Show desc='movie moive' />
                <Button
                    onPress={() => navigate('detail', { user: 'Lucy' })}
                    title='go detail'
                />
            </View>
        );
    }
}


class Music extends Component{
    static navigationOptions={
        title:'音乐',
        tabBarIcon:({})=>(
            <Image
                source={{uri:'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2138215559,1851475130&fm=26&gp=0.jpg'}}
                style={[{width:25,height:24},{}]}
            />
        ),
    };
    render(){
        return(
            <Show desc='音乐' />
        );
    }
}


const MyRoute = createBottomTabNavigator(
    {
        book:{screen:Book},
        movie:{screen: Movie},
        music:{screen:Music}
    },
    {
        // tabBarPosition:"bottom",
    }
);


class Main extends Component{
    static navigationOptions={
        title:'main',
        tabBarIcon:({})=>(
            <Image
                source={{uri:'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2138215559,1851475130&fm=26&gp=0.jpg'}}
                style={[{width:25,height:24},{}]}
            />
        ),
    };
    render(){
        const { navigate } = this.props.navigation;
        return(
            <View style={{flex:1}}>
                <Show desc='main' />
                <Button
                    onPress={() => navigate('list', { user: 'Lucy' })}
                    title="tab list"
                />
            </View>
        );
    }
}



class Detail extends Component{
    static navigationOptions={
        title:'detail',
        tabBarIcon:({})=>(
            <Image
                source={{uri:'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2138215559,1851475130&fm=26&gp=0.jpg'}}
                style={[{width:25,height:24},{}]}
            />
        ),
    };
    render(){
        return(
            <Show desc='detail' />
        );
    }
}



const StackRoute=createStackNavigator({
    main:Main,
    list:MyRoute,
    detail:Detail,
    bookDetail:BookDetail,
},{
    initialRouteName: 'list',
});


type Props = {};
export default class App extends Component<Props> {
    render() {
        return (
            <StackRoute/>
        );
    }
}




const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#F5FCFF',
    },
    welcome: {
        fontSize: 20,
        textAlign: 'center',
        margin: 10,
    },
    instructions: {
        textAlign: 'center',
        color: '#333333',
        marginBottom: 5,
    },
});
